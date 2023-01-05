<template>
  <DialogComponent v-model="isModalOpen" width="800" hide-actions>
    <template #title>
      <span data-qa="role-card__title" class="title">{{ title || (data[0] && data[0].name) }}</span>
    </template>

    <template #content>
      <div v-for="item in data" :key="item.id">
        <span v-if="idList.length > 1" data-qa="role-card__name" class="title">{{ item.name }}</span>
        <p v-if="item.name !== item.description" data-qa="role-card__description">{{ item.description }}</p>
        <h6 v-if="item.authorities.length">Полномочия</h6>
        <ul class="mb-6">
          <li v-for="{ id, name, code, description } in item.authorities" :key="id" data-qa="role-card__authority">
            <b>{{ name === description ? code : `${name} (${code})` }}</b>
            - {{ description }}.
          </li>
        </ul>
      </div>

      <v-row justify="end">
        <v-col cols="12" class="col-exclude">
          <DefaultButton title="Закрыть" @click="isModalOpen = false" />
        </v-col>
      </v-row>
    </template>
  </DialogComponent>
</template>

<script lang="ts">
import { Component, Prop, Mixins } from 'vue-property-decorator';
import DialogComponent from '@/components/common/Dialog/Dialog.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import Modal from '@/utils/global/mixins/modal';
import { RoleItem } from '@/services/mappers/roles';

/** Карточка просмотра роли. */
@Component({
  name: 'RoleCardModal',
  components: { DialogComponent, DefaultButton },
})
export default class RoleCardModal extends Mixins(Modal) {
  static cache: Map<number, RoleItem> = new Map();
  @Prop({ type: [Number, Array] }) readonly id!: number | number[];
  @Prop({ type: String }) readonly title!: string;
  isLoading = false;
  data: RoleItem[] = [];

  get idList() {
    return Array.isArray(this.id) ? this.id : [this.id];
  }

  /** Получить сохраненные данные ролей по переданным id. */
  getFromCache() {
    this.data = this.idList.reduce<RoleItem[]>((result, id) => {
      const item = RoleCardModal.cache.get(id);
      if (item) {
        return [...result, item];
      }

      return result;
    }, []);
  }

  /** Получить информацию по роли. */
  async fetchRole() {
    const response = await Promise.all(this.idList.map((id) => this.$service.roles.findOne(id)));
    response.forEach(({ data }) => {
      RoleCardModal.cache.set(data.id, data);
    });
    this.getFromCache();
  }

  /** Обработчик на открытие. */
  async onModalOpen() {
    this.getFromCache();
    this.isLoading = !this.data;
    await this.fetchRole();
    this.isLoading = false;
  }

  /** Обработчик на закрытие. */
  onModalClose() {
    this.data = [];
  }
}
</script>

<style lang="scss" scoped></style>
