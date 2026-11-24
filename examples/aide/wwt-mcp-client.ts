export type JsonRpc = {
  jsonrpc: "2.0";
  id?: number;
  method?: string;
  params?: Record<string, unknown>;
  result?: unknown;
  error?: { code: number; message: string; data?: { code?: string } };
};

export type WwtMcpClientOptions = {
  endpoint: string;
  token: string;
  workspaceId: string;
  fetchImpl?: typeof fetch;
};

export class WwtMcpClient {
  private nextId = 1;

  constructor(private readonly options: WwtMcpClientOptions) {}

  async initialize(): Promise<JsonRpc> {
    return this.request("initialize", {
      protocolVersion: "2025-03-26",
      capabilities: {},
      clientInfo: { name: "aide", version: "1" },
    });
  }

  async listTools(): Promise<JsonRpc> {
    return this.request("tools/list");
  }

  async cargoOverview(date?: string): Promise<JsonRpc> {
    return this.request("tools/call", {
      name: "wwt_get_cargo_overview",
      arguments: date ? { date } : {},
    });
  }

  async request(method: string, params: Record<string, unknown> = {}): Promise<JsonRpc> {
    const response = await (this.options.fetchImpl ?? fetch)(this.options.endpoint, {
      method: "POST",
      headers: {
        Authorization: `Bearer ${this.options.token}`,
        Accept: "application/json, text/event-stream",
        "Content-Type": "application/json",
        "X-Wwt-Workspace": this.options.workspaceId,
      },
      body: JSON.stringify({
        jsonrpc: "2.0",
        id: this.nextId++,
        method,
        params,
      }),
    });

    const body = (await response.json()) as JsonRpc;
    if (!response.ok || body.error) {
      const code = body.error?.data?.code ?? String(response.status);
      throw new Error(`WWT MCP ${code}: ${body.error?.message ?? "request failed"}`);
    }
    const result = body.result as { isError?: boolean; structuredContent?: { error?: { message?: string } } } | undefined;
    if (result?.isError) {
      throw new Error(result.structuredContent?.error?.message ?? "WWT tool failed");
    }
    return body;
  }
}

export async function readCargoOverview(options: WwtMcpClientOptions): Promise<unknown> {
  const client = new WwtMcpClient(options);
  await client.initialize();
  await client.listTools();
  const overview = await client.cargoOverview();
  return (overview.result as { structuredContent?: unknown }).structuredContent;
}
