<template>
  <v-row v-if="params">
    <v-col cols="5">
      <slot name="title">
        <div class="title">{{ innerTitle }}</div>
        <div class="organization-type">
          <span>{{ innerCaption }}</span>
        </div>
      </slot>
    </v-col>
    <slot name="actions">
      <v-col cols="7" class="justify--end">
        <DefaultButton
          v-if="permitEdit ?? true"
          type="button"
          class="ml-2"
          size="micro"
          prepend-icon="mdi-pencil"
          title="Редактировать"
          variant="primary"
          @click="$emit('edit')"
        />
        <DefaultButton title="Закрыть" @click="$emit('close')" />
      </v-col>
    </slot>
  </v-row>
</template>

<script lang="ts">
import { Vue, Component, Prop } from 'vue-property-decorator';
import { ESubjectType } from '@/services/enums/subject';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';

type TRegisterKey = 'laboratory' | 'manufacturer' | 'ogv';
type ViewCardHeaderParams = {
  subjectType?: ESubjectType;
  registers?: { [key in TRegisterKey]?: boolean };
};

const TitleMap = Object.freeze({
  laboratory: 'Карточка лаборатории',
  ogv: 'Карточка органа государственной власти',
  default: 'Карточка организации',
});

const CaptionMap = Object.freeze({
  [ESubjectType.IP]: 'Индивидуальный предприниматель',
  [ESubjectType.UL]: 'Российское юридическое лицо',
  [ESubjectType.IR]: 'Юридическое лицо, являющееся иностранным лицом',
  [ESubjectType.IF]: 'Аккредитованный филиал представительства иностранного юр. лица',
  default: 'Российское юридическое лицо',
});

@Component({ name: 'ViewCardHeader', components: { DefaultButton } })
export default class ViewCardHeader extends Vue {
  @Prop({ type: Object, default: () => null }) readonly params!: ViewCardHeaderParams | null;
  @Prop({ type: Boolean, default: () => null }) readonly permitEdit!: boolean | null;
  @Prop({ type: String, default: '' }) readonly title!: string;
  @Prop({ type: String, default: '' }) readonly caption!: string;

  get register() {
    const [key] = Object.entries(this.params?.registers || {}).find(([, value]) => value) || [];
    return key || '';
  }

  get innerTitle() {
    if (this.title) {
      return this.title;
    }

    return TitleMap[this.register] || TitleMap.default;
  }

  get innerCaption() {
    if (this.caption) {
      return this.caption;
    }

    return CaptionMap[this.params?.subjectType || ''] || CaptionMap.default;
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.organization-type {
  color: #828286;
}

.justify {
  &--end {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    flex-wrap: wrap;
  }
}
</style>
