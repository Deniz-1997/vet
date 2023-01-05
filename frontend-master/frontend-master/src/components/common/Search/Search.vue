<template>
  <div class="search">
    <div class="search__input">
      <input-component
        v-model="proxyValue"
        :mask="mask"
        :message="message"
        :placeholder="placeholder"
        @keydown.enter="handleSearch"
        @clear="reset"
      />
    </div>
    <div class="search__button">
      <DefaultButton
        variant="primary"
        custom-icon="/icons/loupe.svg"
        alt="Поиск"
        :disabled="disabled"
        @click="handleSearch"
      />
    </div>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Watch, Vue } from 'vue-property-decorator';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import { SETTINGS_KEY } from './consts';

@Component({
  name: 'search-component',
  components: { DefaultButton, InputComponent },
})
export default class SearchComponent extends Vue {
  @Prop({ type: String, default: '' }) value!: string;
  @Prop({ type: String, default: null }) mask!: string;
  @Prop({ type: String, default: '' }) message!: string;
  @Prop({ type: Boolean, default: false }) disabled!: boolean;
  @Prop({ type: String, default: 'Поиск' }) placeholder!: string;

  innerValue = this.value;

  get proxyValue() {
    return this.value || this.innerValue;
  }

  set proxyValue(v) {
    this.innerValue = v;
    localStorage.setItem(SETTINGS_KEY, JSON.stringify({ ...this.settings, [this.$route.path]: v }));
    this.$emit('input', v);
  }

  get settings() {
    return JSON.parse(localStorage.getItem(SETTINGS_KEY) || '{}');
  }

  created() {
    const search = this.settings[this.$route.path];
    if (search) {
      this.proxyValue = (search as string) || this.proxyValue;
      this.handleSearch();
    }
  }

  reset() {
    this.proxyValue = '';
    this.handleSearch();
  }

  handleSearch() {
    this.$emit('search', this.innerValue.trim());
  }

  @Watch('value')
  changeValue() {
    this.innerValue = this.value;
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables.scss';
.search {
  align-items: flex-start;
  display: flex;
  justify-content: space-between;
  position: relative;
  max-width: 380px;
  width: 100%;

  &__input {
    width: 80%;
    min-width: 240px;
    margin-right: 2%;
  }

  .icon {
    cursor: default;
  }
}
</style>
