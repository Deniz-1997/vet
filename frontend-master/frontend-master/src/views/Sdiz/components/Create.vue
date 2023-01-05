<template>
  <v-container v-if="subjectOfUser">
    <sdiz-form
      v-model="model"
      :is-create="true"
      :is-elevator="isElevatorPage"
      :subject-id="subject_id"
      :title-page-create="titlePageCreate"
      :hide-ammends-menu="true"
    >
      <template #buttons>
        <v-col cols="12">
          <v-row class="ma-0" justify="end">
            <v-col class="right-align mb-3 mr-4" cols="12">
              <text-component class="text-caption text-center orange--text" variant="span">
                {{ model.getError() }}
              </text-component>
            </v-col>

            <button-component
              class="mr-7"
              size="micro"
              title="Вернуться в реестр"
              @click="$router.push({ name: model.name_route_list })"
            />

            <button-component
              :disabled="model.getError() !== ''"
              :loading="isLoading"
              size="micro"
              title="Сформировать"
              variant="primary"
              @click="handleCreate"
            />
          </v-row>
        </v-col>
      </template>
      <template #[`manufacture-field`]="{ onSearchUpdateMix, items, model, disabled }">
        <slot
          :items="items"
          :model="model"
          :disabled="disabled"
          :on-search-update-mix="onSearchUpdateMix"
          name="manufacture-field"
        />
      </template>

      <template #[`date-create`]="{ model, disabled }">
        <slot :model="model" :disabled="disabled" name="date-create" />
      </template>

      <template #[`product-type-field`]="{ model, disabled }">
        <slot :model="model" :disabled="disabled" name="product-type-field" />
      </template>
    </sdiz-form>

    <v-overlay :value="isLoading" z-index="10">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </v-container>
</template>

<script lang="ts">
import { Component, Model, Prop } from 'vue-property-decorator';
import Sdiz from '@/views/Sdiz/Sdiz.vue';
import { LotDataVueModel } from '@/models/Lot/Data/LotData.vue';
import TextComponent from '@/components/common/Text/Text.vue';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';
import SdizForm from '@/views/Sdiz/components/Form.vue';
import { SdizGpbVueModel } from '@/models/Sdiz/Data/SdizGpb.vue';
import { SdizVueModel } from '@/models/Sdiz/Data/Sdiz.vue';
import { LotGpbDataVueModel } from '@/models/Lot/Data/LotGpbData.vue';
import { SdizElevatorModel } from '@/models/Sdiz/Data/SdizElevator.vue';
import { SdizCarrierModel } from '@/models/Sdiz/SdizCarrier';
import { currentDay } from '@/utils';

@Component({
  name: 'sdiz-create',
  components: { SdizForm, TextComponent, ButtonComponent },
})
export default class SdizCreate extends Sdiz {
  @Model('change', { type: Object, required: true }) model!: SdizGpbVueModel | SdizVueModel | SdizElevatorModel;

  @Prop({ type: Boolean, required: true }) readonly isElevatorPage!: boolean;

  @Prop({ type: String }) readonly titlePageCreate!: string;

  optionNotify = [
    { group: 'lot', type: 'warning', title: 'Некорректный ID партии зерна', text: '' },
    {
      group: 'sdiz',
      type: 'warning',
      title: 'Указанная партия не найдена или недоступна для создания СДИЗ',
      text: 'Выберите партию вручную',
    },
    {
      group: 'sdiz',
      type: 'warning',
      title: 'В данный момент невозможно автоматический указать партию',
      text: 'Выберите партию вручную',
    },
  ];

  isShipping() {
    this.model.objects.operations.detail.shipping = !this.model.objects.operations.detail.shipping;
  }

  get isElevatorComponent() {
    return this.model.component_name === 'sdiz_elevator';
  }

  async handleCreate(): Promise<void> {
    try {
      const errors = this.model.getErrors();
      if (errors.length > 0 && !this.isElevatorPage) {
        errors.forEach((v) => this.$notify({ group: 'sdiz', type: 'warning', title: v, text: '' }));
        return;
      }
      this.createLink = this.model.create_apiendpoit;
      this.afterCreatePush = this.model.name_route_detail;
      this.isLoading = true;

      let data: any = this.model.getDataForCreate();
      data.elevator_creator = this.isElevatorPage;
      await this.createActionMix(data);
      this.isLoading = false;
    } catch (e) {
      if (this.errors !== undefined) return this.errors;
      this.isLoading = false;
    }
  }
  newLotObject(response): void {
    if (typeof response === 'object') {
      const lot = response;

      if (typeof lot.gpb_number === 'undefined') {
        this.model.objects.lot = new LotDataVueModel(lot);
      } else {
        this.model.objects.gpb = new LotGpbDataVueModel(lot);
      }
    } else {
      this.$notify(this.optionNotify[1]);
    }
  }

  async onLoadOtherLot(): Promise<void> {
    const id = this.$route.query.lot_id;
    if (typeof id === 'undefined') {
      this.$notify(this.optionNotify[0]);
    } else {
      const { status, response } = await this.$store.dispatch(this.model.getObjectLot().show_apiendpoit, id);
      if (status) {
        if ((response.purpose['code'] === '1' || response.purpose['code'] === '2') && !this.isElevatorComponent) {
          this.model.objects.operations.prototype_sdiz = 1;
        } else if (response.purpose['code'] === '3' && !this.isElevatorComponent) {
          this.model.objects.operations.prototype_sdiz = 2;
          this.isShipping();
        } else if (response.purpose['code'] === '4' && !this.isElevatorComponent) {
          this.model.objects.operations.prototype_sdiz = 3;
          this.isShipping();
        } else {
          this.model.objects.operations.prototype_sdiz = 1;
        }
        this.newLotObject(response);
      } else {
        this.$notify(this.optionNotify[2]);
      }
    }
  }

  async created(): Promise<void> {
    await Promise.all([this.fetchOkpd2Msh(this.model.getObjectLot().lotType), this.fetchTypes()]);
    if (typeof this.$route.query.lot_id !== 'undefined') {
      await this.onLoadOtherLot();
    }

    if (this.$route.params.sdizToCopyId) {
      await this.handleSdizCopy(this.$route.params.sdizToCopyId);
    }

    this.isLoading = !this.isLoading;

    if (this.subjectOfUser !== undefined) {
      this.subject_id = this.subjectOfUser.subject_id;
      this.subject_name = this.subjectName;
    }
  }

  clearIds(data: any[]) {
    if (!Array.isArray(data)) return data;

    return data.map((e) => {
      e.id = null;
      return e;
    });
  }

  clearIdsForSdizCopy(
    model: SdizGpbVueModel | SdizVueModel | SdizElevatorModel
  ): SdizGpbVueModel | SdizVueModel | SdizElevatorModel {
    model.getObjectLot().id = null;
    model.carriers = this.clearIds(model.carriers).map((e: SdizCarrierModel) => {
      e.doc_transports = this.clearIds(e.doc_transports);
      e.locations = this.clearIds(e.locations);
      return e;
    });

    model.objects.docs_transports_other = this.clearIds(model.objects.docs_transports_other);
    model.objects.docs_other = this.clearIds(model.objects.docs_other);
    model.objects.docs_akt = this.clearIds(model.objects.docs_akt);

    return model;
  }

  async handleSdizCopy(id) {
    try {
      const { response, status } = await this.$store.dispatch(this.model.show_apiendpoit, id);

      if (!status || response.length === 0) throw new Error();

      const newModel: SdizGpbVueModel | SdizVueModel | SdizElevatorModel = this.callbackLoadList(
        this.model,
        [],
        [response]
      )[0];

      newModel.enter_date = currentDay();

      this.$emit('change', this.clearIdsForSdizCopy(newModel));
    } catch (_e) {
      this.$service.notify.push('error', { text: 'Ошибка при получении данных СДИЗ для копирования' });
    }
  }
}
</script>
