import { Container } from "@/components/shared/Container";

export function PageHero({ title, subtitle }: { title: string; subtitle?: string }) {
  return (
    <section className="bg-gradient-to-b from-brand-50/70 to-white py-16 sm:py-20">
      <Container className="max-w-3xl text-center">
        <h1 className="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
          {title}
        </h1>
        {subtitle && <p className="mt-4 text-lg text-slate-600">{subtitle}</p>}
      </Container>
    </section>
  );
}
