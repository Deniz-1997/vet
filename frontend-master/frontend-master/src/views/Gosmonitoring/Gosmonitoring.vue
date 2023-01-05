<template>
  <div class="main">
    <notifications group="gosmonitoring" position="bottom right" />
    <notifications group="notifications-m" position="bottom right" />
    <router-view></router-view>
  </div>
</template>

<script lang="ts">
import { Component, Prop } from 'vue-property-decorator';
import moment from 'moment';
import { mixins } from 'vue-class-component';
import { AdditionalMix } from '@/utils/mixins/additional';
import { FiasMix } from '@/utils/mixins/fias';
import { Manufactures } from '@/utils/mixins/manufactures';
import { RequestMix } from '@/utils/mixins/request';
import { ActionsMix } from '@/utils/mixins/actions';
import { PermissionMix } from '@/utils/mixins/permission';

moment.locale('ru');

@Component({
  name: 'gosmonitoring',
})
export default class Gosmonitoring extends mixins(
  AdditionalMix,
  FiasMix,
  Manufactures,
  RequestMix,
  ActionsMix,
  PermissionMix
) {
  @Prop({ type: String, default: 'АРМ Государственный мониторинг' }) public title!: string;

  model: any = {};
  url = '';
  clear = 0;
  getList = 'gosmonitoring/getList';
  isClearFiltersAndReloadRows = false;
  isLoading = false;

  async onUpdate(): Promise<void> {
    try {
      this.isLoading = true;

      const id: number = this.model.id as number;
      const { response, status } = await this.$store.dispatch('gosmonitoring/update', {
        id: id,
        data: {
          url: this.url,
          data: this.model.getDataForCreate(),
        },
      });

      if (!status) throw new Error();
      else this.getNotify('Выполнено успешно.');
      await this.$router.push({
        name: this.model.update_apiendpoit,
        params: { id: response.id },
      });
    } catch (_e) {
      this.getNotify('Ошибка при выполнении операции', 'warning');
    } finally {
      this.isLoading = false;
    }
  }

  async onCreate(): Promise<void> {
    try {
      if (this.url !== 'register/research-register') {
        this.model.owner_id = this.subjectOfUser.subject_id;
      }

      this.isLoading = true;

      const { response, status } = await this.$store.dispatch('gosmonitoring/create', {
        url: this.url,
        data: this.model.getDataForCreate(),
      });

      if (!status) throw new Error();
      this.getNotify('Сохранено успешно.');
      await this.$router.push({ name: this.model.update_apiendpoit, params: { id: response.id } });
    } catch (_e) {
      this.getNotify('Ошибка при выполнении операции', 'warning');
    } finally {
      this.isLoading = false;
    }
  }

  async onDelete(): Promise<void> {
    this.isLoading = true;

    const { status } = await this.$store.dispatch('gosmonitoring/delete', {
      url: this.url,
      id: this.model.id,
    });

    if (status) {
      await this.$router.push({ name: this.model.cancel_link });
    } else {
      this.getNotify('Выполнено успешно.');
    }

    this.isLoading = false;
  }

  getNotify(text: string, type = 'success'): void {
    this.$notify({ group: 'gosmonitoring', type, title: '', text: text });
  }

  setIsLoading(value: boolean) {
    this.isLoading = value;
  }
}
</script>
