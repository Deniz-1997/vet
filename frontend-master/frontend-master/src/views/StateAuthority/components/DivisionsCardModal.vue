<template>
  <DialogComponent v-model="isModalOpen" width="800" hide-actions>
    <template #title>
      <span data-qa="role-card__title" class="title">{{ title }}</span>
    </template>

    <template #content>
      <v-row>
        <v-col cols="12" md="6">
          <v-row>
            <v-col cols="12">
              <SelectComponent
                v-model="divisionsForm.root_division"
                return-object
                :items="divisionList"
                label="Вышестоящее подразделение"
                item-value="code"
                item-text="name"
                :is-disabled="divisionList.length === 0"
              />
            </v-col>
          </v-row>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12">
          <label-component label="Наименование подразделения или должности" />
          <InputComponent id="name" v-model="divisionsForm.name" placeholder="Введите текст" :disabled="isShow" />
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12">
          <EditableTable
            v-model="divisionsForm.division_staff"
            title="Сотрудники"
            :is-showcase="isShow"
            :is-edit-action="!isShow"
            :is-can-edit="!isShow"
            :options="headersStaff"
            :max="999"
          />
        </v-col>
      </v-row>

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
import TextareaComponent from '@/components/common/inputs/TextAreaComponent.vue';
import Modal from '@/utils/global/mixins/modal';
import InputComponent from '@/components/common/inputs/InputComponent.vue';

/** Карточка просмотра роли. */
@Component({
  name: 'DivisionsCardModal',
  components: { DialogComponent, DefaultButton, TextareaComponent, InputComponent },
})
export default class DivisionsCardModal extends Mixins(Modal) {
  @Prop({ type: String, default: 'Подразделение' }) readonly title!: string;
  divisionList = [];
  divisionsForm = {
    root_division: null,
    division_staff: null,
    name: '',
  };
  stafflist: any = [];
  isShow = true;

  get headersStaff() {
    return [
      {
        label: 'ФИО',
        name: 'staff',
        controlType: 'autoComplete',
        itemValue: 'value',
        itemText: 'label',
        restrictions: this.staffList,
        exclude: true,
        width: 250,
      },
      {
        label: 'Дата назначения',
        name: 'start_date',
        controlType: 'date',
        limitTo: this.$moment().add(1, 'd').toDate(),
        width: 250,
      },
      {
        label: 'Дата снятия',
        name: 'end_date',
        controlType: 'date',
        width: 250,
      },
    ];
  }
}
</script>

<style lang="scss"></style>
