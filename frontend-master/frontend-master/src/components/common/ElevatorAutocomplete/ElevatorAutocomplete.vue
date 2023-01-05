<template>
  <div>
    <v-tooltip top max-width="500" open-delay="500">
      <template #activator="{ on, activatorAttrs }">
        <span v-if="placeholder" v-bind="activatorAttrs" class="hint_span" v-on="on">
          <AutocompleteComponent
            v-model="innerValue"
            :items="options"
            :label="label"
            no-data-text="Если организации нет в списке, попробуйте уточнить запрос"
            item-value="subject_id"
            item-text="name"
            :is-disabled="isDisabled"
            :clearable="clereables"
            :placeholder="placeholder"
            :filter="filter"
            return-object
            @onChange="searchString = ''"
            @searchInputUpdate="onInput"
            clearable
          >
            <template #item="{ item, attrs }">
              <div v-bind="attrs" :key="item.subject_id" class="d-flex">
                <div>
                  <UiHighlightedText
                    v-if="item.name"
                    class="organization-picker__text"
                    :text="item.name"
                    :search="searchString"
                    tag="div"
                  />
                  <span class="organization-picker__caption">
                    <span v-if="item.inn">
                      ИНН:
                      <UiHighlightedText :text="item.inn" :search="searchString" />
                    </span>
                    <span v-if="item.kpp">
                      КПП:
                      <UiHighlightedText :text="item.kpp" :search="searchString" />
                    </span>
                    <span v-if="item.ogrn">
                      ОГРН:
                      <UiHighlightedText :text="item.ogrn" :search="searchString" />
                    </span>
                  </span>
                </div>
              </div>
            </template>
          </AutocompleteComponent>
        </span>
      </template>
      {{ placeholder }}
    </v-tooltip>
  </div>
</template>

<script lang="ts">
import { Component, Vue, Prop, Model } from 'vue-property-decorator';
import isEmpty from 'lodash/isEmpty';
import { Debounce } from '@/utils/global/decorators/method';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';

@Component({
  name: 'ElevatorAutocomplete',
  components: { AutocompleteComponent },
})
export default class ElevatorAutocomplete extends Vue {
  @Model('change', { type: [Object, Array, Number], default: null }) readonly value!: any;
  @Prop({ type: String, required: true }) readonly label!: string;
  @Prop({ type: Boolean, default: false }) readonly includingClosed!: boolean;
  @Prop({ type: Boolean }) readonly readonly!: boolean;
  @Prop({ type: Boolean }) readonly multi!: boolean;
  @Prop({ type: String, required: false, default: 'Выберите элеватор' }) readonly placeholder!: string;

  @Prop({ type: Boolean, required: false, default: false }) readonly clereables!: boolean;

  @Prop({ type: Boolean, required: false, default: false }) readonly isDisabled!: boolean;
  items: any[] = [];
  searchString = '';
  isLoading = false;
  temporaryValue: any = null;

  get innerValue() {
    if (typeof this.value === 'number') {
      return {
        subject_id: this.value,
      };
    }
    return this.value;
  }

  set innerValue(v: any) {
    this.temporaryValue = v;
    this.$emit('change', !isEmpty(v) ? v.subject_id : null);
  }

  get options() {
    return this.items.map((item) => this.normalize(item));
  }

  get filter() {
    return ({ name, inn, ogrn, kpp }: any, query = '') => {
      return (
        (name || '').toLowerCase().includes(query.toLowerCase()) ||
        (inn || '').toLowerCase().includes(query.toLowerCase()) ||
        (ogrn || '').toLowerCase().includes(query.toLowerCase()) ||
        (kpp || '').toLowerCase().includes(query.toLowerCase())
      );
    };
  }

  async created() {
    if (this.value) {
      const { data } = await this.$axios.get(`/api/elevator/elevator/subject/${this.value}`);
      this.temporaryValue = {
        ...data,
        name: `${data.registration_number} ${data.name}`,
      };
      this.items = [...this.items, { ...this.temporaryValue }];
    }
  }

  normalize(item: any = {}): any {
    return { ...item, short_name: item.name };
  }

  @Debounce(4000)
  async onInput(searchString: any) {
    this.isLoading = true;
    this.searchString = searchString?.name ?? (searchString || '');
    let items: any[] = [...this.items];
    if (
      !(
        this.temporaryValue &&
        searchString &&
        searchString.includes(this.temporaryValue.name || this.temporaryValue.short_name)
      ) &&
      this.searchString?.length > 3
    ) {
      items = await this.fetchList(this.searchString);
    }

    this.items = items;
    this.isLoading = false;
  }

  async fetchDefaultList() {
    return await this.fetchList('');
  }

  async fetchList(filter: string) {
    const { data } = await this.$axios.post('/api/elevator/elevatorItems', {
      filter,
      actual: true,
      is_elevator: true,
    });

    const list =
      data.content.map((item) => ({
        ...item,
        name: `${item.registration_number} ${item.name}`,
      })) || [];
    if (this.value && !list.find((item) => item.subject_id === this.value)) {
      list.unshift((await this.$axios.get(`/api/elevator/elevator/subject/${this.value}`)).data.subject);
    }
    return list;
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';

.organization-picker {
  &__caption {
    font-size: 11px;
    color: $medium-grey-color;
  }

  &__text {
    font-size: 13px;
  }
}
</style>
