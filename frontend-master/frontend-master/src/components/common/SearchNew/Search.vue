<template>
  <div class="search">
    <div class="search__input">
      <input-component
        v-model="proxyValue"
        :placeholder="placeholder"
        @keydown.enter="handleSearch"
        @clear="reset"
      >
        <template #append>
          <img
            v-if="!proxyValue"
            src="/icons/search.svg"
            class="iconTable"
          />
        </template>
      </input-component>
    </div>
    <div class="searc__button">
      <DefaultButton title="Найти" variant="primary" alt="Поиск" @click="handleSearch" />
    </div>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import { SETTINGS_KEY } from './consts';

@Component({
  name: 'search-new-component',
  components: { DefaultButton, InputComponent },
})
export default class SearchNewComponent extends Vue {
  @Prop({ type: String, default: '' }) value!: string;
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
}
</script>

<style lang="scss">
@import '@/assets/styles/_variables.scss';
.search {
  align-items: center;
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
