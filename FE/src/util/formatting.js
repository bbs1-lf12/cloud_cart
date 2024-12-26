export const currencyFormatter = new Intl.NumberFormat('de-DE', {
    style: 'currency',
    currency: 'EUR',
  });

// sinvoll wenn man mehrere Währungen hat

export const centsToPrice = (cents) => cents / 100;
