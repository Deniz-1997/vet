export default function address({
  country,
  address,
}: {
  country: { name_full: string; code_alpha2: string };
  address: string;
}) {
  if (!country?.code_alpha2 || country.code_alpha2 === 'RU') {
    return address || '';
  }

  return `${country.name_full}, ${address}`;
}
