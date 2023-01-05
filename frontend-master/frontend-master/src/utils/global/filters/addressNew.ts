export default function addressNew({
  country,
  addressText,
}: {
  country: { name_full: string; code_alpha2: string };
  addressText: string;
}) {
  if (country) {
    if (!country?.code_alpha2 || country.code_alpha2 === 'RU') {
      return addressText || '';
    }

    return `${country.name_full}, ${addressText}`;
  } else {
    return addressText || '';
  }
}
