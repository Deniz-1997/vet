<template>
  <div class="address">
    <v-row v-if="form.subjectType === 'UL' || (form.subjectType === 'IP' && form.address)">
      <v-col cols="12" md="6" xl="6">
        <InputComponent :value="subjectAddress" label="Адрес" disabled />
      </v-col>

      <v-col cols="12" md="4" xl="4">
        <div class="inputContainer">
          <div class="element">
            <InputComponent label="Регион" disabled :value="form.regionName" />
          </div>
        </div>
      </v-col>
      <v-col cols="12" md="2" xl="2">
        <div class="inputContainer">
          <div class="element">
            <InputComponent label="Код региона" disabled :value="form.regionCode" />
          </div>
        </div>
      </v-col>
    </v-row>

    <v-row>
      <v-col v-if="(form.subjectType === 'UL' || form.subjectType === 'IP') && subjectAddressAdditionalInfo" cols="12">
        <InputComponent
          id="additional_info"
          :value="subjectAddressAdditionalInfo"
          disabled
          label="Дополнительный адрес"
        />
      </v-col>
    </v-row>
  </div>
</template>

<script lang="ts">
import { Component, Vue, Prop } from 'vue-property-decorator';
import InputComponent from '@/components/common/inputs/InputComponent.vue';

/**ToDo: У форм прописать полную типизацию */
type Props = {
  form: any;
};

@Component({
  name: 'card-address',
  components: { InputComponent },
})
export default class CardAddress extends Vue {
  @Prop({ type: Object, default: () => ({}) }) readonly form!: Props['form'];

  get subjectAddress() {
    return this.form?.address?.addressText ?? '-';
  }

  get subjectAddressAdditionalInfo() {
    return this.form?.address?.additionalInfo ?? '';
  }
}
</script>
