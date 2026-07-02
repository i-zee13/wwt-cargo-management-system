"use client";

import { motion } from "framer-motion";
import { Package, Plane, Ship, MapPin } from "lucide-react";

export function HeroVisual() {
  return (
    <div className="relative mx-auto aspect-square w-full max-w-md">
      <div className="absolute inset-0 rounded-full bg-gradient-to-br from-brand-100 via-brand-50 to-white" />

      <motion.div
        className="absolute left-1/2 top-1/2 h-[70%] w-[70%] -translate-x-1/2 -translate-y-1/2 rounded-full border-2 border-dashed border-brand-200"
        animate={{ rotate: 360 }}
        transition={{ duration: 30, repeat: Infinity, ease: "linear" }}
      />

      <motion.div
        className="absolute left-1/2 top-1/2 flex h-24 w-24 -translate-x-1/2 -translate-y-1/2 items-center justify-center rounded-3xl bg-brand-600 text-white shadow-xl shadow-brand-600/30"
        animate={{ y: [0, -8, 0] }}
        transition={{ duration: 3, repeat: Infinity, ease: "easeInOut" }}
      >
        <Package size={40} />
      </motion.div>

      <motion.div
        className="absolute right-4 top-8 flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-brand-600 shadow-lg"
        animate={{ y: [0, -10, 0], rotate: [0, 6, 0] }}
        transition={{ duration: 4, repeat: Infinity, ease: "easeInOut", delay: 0.3 }}
      >
        <Plane size={24} />
      </motion.div>

      <motion.div
        className="absolute bottom-8 left-2 flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-brand-600 shadow-lg"
        animate={{ y: [0, 8, 0] }}
        transition={{ duration: 3.4, repeat: Infinity, ease: "easeInOut", delay: 0.6 }}
      >
        <Ship size={24} />
      </motion.div>

      <motion.div
        className="absolute bottom-4 right-10 flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-brand-600 shadow-lg"
        animate={{ scale: [1, 1.12, 1] }}
        transition={{ duration: 2.4, repeat: Infinity, ease: "easeInOut" }}
      >
        <MapPin size={20} />
      </motion.div>
    </div>
  );
}
