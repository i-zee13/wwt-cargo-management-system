import {
  Mailbox,
  ShoppingCart,
  Warehouse,
  SearchCheck,
  Package,
  Plane,
  Undo2,
  MapPin,
  type LucideIcon,
} from "lucide-react";

const ICONS: Record<string, LucideIcon> = {
  mailbox: Mailbox,
  "shopping-cart": ShoppingCart,
  warehouse: Warehouse,
  "search-check": SearchCheck,
  package: Package,
  plane: Plane,
  undo: Undo2,
  "map-pin": MapPin,
};

export function ServiceIcon({ name, size = 22 }: { name: string; size?: number }) {
  const Icon = ICONS[name] ?? Package;
  return <Icon size={size} />;
}
