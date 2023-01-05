export interface PutPasswordRecoveryInterface {
  recipient: string;
  code: string;
  newPassword: string;
  newPasswordConfirm: string;
}
