export interface DadataSuggestion {
  value: string;
  unrestricted_value: string;
  data: {
    surname: string | null;
    name: string | null;
    patronymic: string | null;
    gender: string | null;
    qc: string | null;
  };
}
