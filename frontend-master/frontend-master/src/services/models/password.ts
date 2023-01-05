export type TPasswordBaseForm<T> = T & {
  password: string;
  confirmPassword: string;
};

export type TPasswordChangeForm = TPasswordBaseForm<{
  userId: number;
}>;

export type TPasswordResetForm = TPasswordBaseForm<{
  uuid: string;
}>;
