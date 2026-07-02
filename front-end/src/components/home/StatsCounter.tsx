"use client";

import { useEffect, useRef, useState } from "react";
import { useTranslations } from "next-intl";
import { motion, useInView, useReducedMotion } from "framer-motion";
import { Container } from "@/components/shared/Container";

type StatItem = { value: string; suffix: string; label: string };

function Counter({ value, suffix }: { value: string; suffix: string }) {
  const ref = useRef<HTMLSpanElement>(null);
  const inView = useInView(ref, { once: true, margin: "-40px" });
  const prefersReducedMotion = useReducedMotion();
  const numericTarget = parseInt(value, 10);
  const isNumeric = !Number.isNaN(numericTarget);
  const [display, setDisplay] = useState(isNumeric ? 0 : numericTarget);

  useEffect(() => {
    if (!inView || !isNumeric) return;

    if (prefersReducedMotion) {
      setDisplay(numericTarget);
      return;
    }

    const duration = 1200;
    const start = performance.now();

    const step = (now: number) => {
      const progress = Math.min((now - start) / duration, 1);
      setDisplay(Math.round(progress * numericTarget));
      if (progress < 1) requestAnimationFrame(step);
    };

    requestAnimationFrame(step);
  }, [inView, isNumeric, numericTarget, prefersReducedMotion]);

  return (
    <span ref={ref} className="text-4xl font-bold text-accent-500 sm:text-5xl">
      {isNumeric ? display : value}
      {suffix}
    </span>
  );
}

export function StatsCounter() {
  const t = useTranslations("stats");
  const items = t.raw("items") as StatItem[];

  return (
    <section className="bg-brand-600 py-16">
      <Container>
        <div className="grid grid-cols-2 gap-8 text-center lg:grid-cols-4">
          {items.map((item, i) => (
            <motion.div
              key={item.label}
              initial={{ opacity: 0, y: 12 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.4, delay: i * 0.06 }}
            >
              <Counter value={item.value} suffix={item.suffix} />
              <p className="mt-2 text-sm font-medium text-brand-200">{item.label}</p>
            </motion.div>
          ))}
        </div>
      </Container>
    </section>
  );
}
