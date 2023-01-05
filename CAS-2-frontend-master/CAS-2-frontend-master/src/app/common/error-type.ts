export enum ErrorType {
  GetToken = 'GetToken',
  GetUser = 'GetUser',
  PasswordRestore = 'PasswordRestore',
  PasswordChange = 'PasswordChange',
}

export interface ErrorStore {
  message: string;
}
