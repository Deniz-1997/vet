<template>
  <div data-qa="subject-verification-info">
    <v-row v-if="!isLoading">
      <v-col cols="12" data-qa="subject-verification-info__date">
        <span><b>Дата последней проверки:</b> {{ lastVerificationDate }}</span>
      </v-col>
      <v-col cols="12" data-qa="subject-verification-info__type">
        <span><b>Тип выявленного нарушения:</b> {{ violationType }}</span>
      </v-col>
      <v-col cols="12" data-qa="subject-verification-info__difference">
        <span>
          <b>Выявленные расхождения:</b>
          {{ violation.difference }}
        </span>
      </v-col>
    </v-row>
    <v-overlay :value="isLoading" :absolute="true">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </div>
</template>

<script lang="ts">
import { Component, Vue, Prop } from 'vue-property-decorator';
import { ViolationItem } from '@/services/mappers/violation';
import { date } from '@/utils/global/filters';

@Component({
  name: 'SubjectVerificationInfo',
})
export default class SubjectVerificationInfo extends Vue {
  @Prop({ type: Number }) readonly id!: number;
  isLoading = true;
  violation: ViolationItem | null = null;

  async created() {
    const response = await this.$axios.get(`/api/smev/verification/${this.id}`);

    this.violation = new ViolationItem(response.data.violation);
    this.isLoading = false;
  }

  get lastVerificationDate() {
    const { created, subject } = this.violation || {};
    const lastDate = subject?.lastVerificationDate || created || new Date();
    return date(lastDate, { outputFormat: 'DD.MM.YYYY' });
  }

  get violationType() {
    return this.violation?.type.name;
  }
}
</script>
