import { LARAVEL_URL } from "@/lib/constants";
import type { TrackingResponse } from "@/types/tracking";

export async function trackPackage(waybill: string): Promise<TrackingResponse> {
  const trimmed = waybill.trim();

  if (!trimmed) {
    return { status: "error", message: "empty" };
  }

  try {
    const res = await fetch(
      `${LARAVEL_URL}/api/track/${encodeURIComponent(trimmed)}`,
      { headers: { Accept: "application/json" }, cache: "no-store" }
    );

    if (res.status === 404) {
      return { status: "not_found" };
    }

    if (!res.ok) {
      return { status: "error" };
    }

    const json = await res.json();

    if (json.status === "not_found") {
      return { status: "not_found" };
    }

    return {
      status: "success",
      data: {
        waybill: json.data.waybill,
        status: json.data.status,
        kg: json.data.kg ?? null,
        cbm: json.data.cbm ?? null,
        createdAt: json.data.created_at ?? null,
      },
    };
  } catch {
    return { status: "error" };
  }
}
