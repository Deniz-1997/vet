import { Mapper } from '@/utils';
import { TPasswordChangeForm, TPasswordResetForm } from '@/services/models/password';

export class PasswordRecoveryOut extends Mapper<TPasswordResetForm> {
  @Mapper.catch()
  get uuid() {
    return this.get(({ uuid }) => uuid).required.value;
  }

  @Mapper.catch()
  get new_password() {
    return this.get(({ password }) => password).required.value;
  }

  @Mapper.catch()
  get new_password_repeat() {
    return this.get(({ confirmPassword }) => confirmPassword).required.value;
  }

  toJSON() {
    return {
      uuid: this.uuid,
      new_password: this.new_password,
      new_password_repeat: this.new_password_repeat,
    };
  }
}

export class PasswordChangeOut extends Mapper<TPasswordChangeForm> {
  @Mapper.catch()
  get user_id() {
    return this.get(({ userId }) => userId).required.value;
  }

  @Mapper.catch()
  get new_password() {
    return this.get(({ password }) => password).required.value;
  }

  @Mapper.catch()
  get new_password_repeat() {
    return this.get(({ confirmPassword }) => confirmPassword).required.value;
  }

  toJSON() {
    return {
      user_id: this.user_id,
      new_password: this.new_password,
      new_password_repeat: this.new_password_repeat,
    };
  }
}
