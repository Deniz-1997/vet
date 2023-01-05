<template>
  <div>
    <v-tooltip top max-width="500" open-delay="500">
      <template #activator="{ on, activatorAttrs }">
        <span v-if="placeholder" v-bind="activatorAttrs" class="hint_span" v-on="on">
          <AutocompleteComponent
            v-model="innerValue"
            :items="options"
            :label="label"
            item-value="id"
            item-text="name"
            :is-disabled="isDisabled"
            :clearable="clereables"
            :placeholder="placeholder"
            :name="name"
            return-object
            @onChange="searchString = ''"
            @searchInputUpdate="onInput"
          />
        </span>
      </template>
      {{ placeholder }}
    </v-tooltip>
  </div>
</template>

<script lang="ts">
import { Component, Vue, Prop, Watch, Model } from 'vue-property-decorator';
import isEmpty from 'lodash/isEmpty';
import { Debounce } from '@/utils/global/decorators/method';
import AutocompleteComponent from '@/components/common/inputs/AutocompleteComponent.vue';

@Component({
  name: 'RailwayStationDictionary',
  components: { AutocompleteComponent },
})
export default class RailwayStationDictionary extends Vue {
  @Model('change', { type: [Object, Array, Number], default: null }) readonly value!: any;
  @Prop({ type: [Object, Array, Number], required: false, default: () => [] }) readonly itemsList!: any;
  @Prop({ type: String, required: true }) readonly label!: string;
  @Prop({ type: String, required: false, default: 'Выберите ж/д станцию' }) readonly placeholder!: string;
  @Prop({ type: String, required: false, default: '' }) readonly defaultValue!: string;
  @Prop({ type: Boolean, required: false, default: false }) readonly clereables!: boolean;
  @Prop({ type: Boolean, required: false, default: false }) readonly isDisabled!: boolean;
  @Prop({ type: String, default: '' }) name!: string;

  items: any[] = this.itemsList;
  searchString = '';
  isLoading = false;

  get innerValue() {
    if (typeof this.value === 'number') {
      return {
        id: this.value,
      };
    }
    return this.value;
  }

  set innerValue(v: any) {
    this.$emit('change', !isEmpty(v) ? v : null);
  }

  get options() {
    return this.items.map((item) => this.normalize(item));
  }

  normalize(item: any = {}): any {
    return { ...item, name: item.name };
  }

  beforeMount() {
    if (this.value) {
      this.items.push(this.value);
    } else if (this.defaultValue) {
      this.setDefaultValue();
    }
  }

  @Debounce(1000)
  async onInput(searchString: any) {
    this.isLoading = true;
    this.searchString = searchString || '';
    if (searchString && this.searchString?.length > 3) {
      await this.fetchList(this.searchString);
    }

    if (!this.searchString) {
      this.items = [];
    }

    this.isLoading = false;
  }

  async setDefaultValue() {
    await this.fetchList(this.defaultValue);

    if (this.items.length) {
      this.innerValue = this.items[0];
    }

    this.$emit('setDefault');
  }

  async fetchList(filter?: string) {
    let { data } = await this.$axios.post('/api/elevator-request/railwayStation', {
      filter,
      pageable: {
        pageNumber: 0,
        pageSize: 15,
      },
    });
    this.items = data.content;
  }

  @Watch('itemsList')
  changeItemsList() {
    this.items = this.itemsList;
  }
}
</script>
