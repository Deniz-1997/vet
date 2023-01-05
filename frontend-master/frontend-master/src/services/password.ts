import { TPasswordChangeForm } from './models/password';
import { Service } from '@/plugins/service';
import { TPasswordResetForm } from '@/services/models/password';
import { PasswordChangeOut, PasswordRecoveryOut } from '@/services/mappers/password';

export default class Password extends Service {
  change(form: TPasswordChangeForm) {
    return this.$axios.post('/api/security/user/changePassword', new PasswordChangeOut(form));
  }

  async applyReset(login: string) {
    return this.$axios.get(`api/auth/password/reset-request/${login}`);
  }

  async reset(form: TPasswordResetForm) {
    try {
      const response = await this.$axios.post('/api/auth/password/reset', new PasswordRecoveryOut(form));
      this.$router.push('/login');
      return response;
    } catch (error) {
      this.$router.push('/login?error=password-recovery');
      throw error;
    }
  }
}
