export type PackageStatus =
  | "received"
  | "in-progress"
  | "embarked"
  | "arrived"
  | "retired";

export type TrackingResult = {
  waybill: string;
  status: PackageStatus | string;
  kg: number | string | null;
  cbm: number | string | null;
  createdAt: string | null;
};

export type TrackingResponse =
  | { status: "success"; data: TrackingResult }
  | { status: "not_found" }
  | { status: "error"; message?: string };
