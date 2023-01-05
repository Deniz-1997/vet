export type TFileItem = {
  /** Название документа. */
  label?: string;
  /** Путь для скачивания. */
  name: string;
  /** Дата публикации. */
  date: string | Date;
};

export type TFileDirectory = 'documents' | 'video';
