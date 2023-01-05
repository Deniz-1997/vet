<template>
  <v-container>
    <div class="title">
      <span>Справочники</span>
    </div>
    <v-row>
      <v-col v-for="{ id, to, label } in items" :key="id" cols="12" md="6" lg="6" xl="6">
        <router-link :to="to" class="nsi-list__item">{{ label }}</router-link>
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import config from '@/views/NSI/config';
import { RawLocation } from 'vue-router';

type TDictionaryItem = {
  id: string;
  to: RawLocation;
  label: string;
};

type TConfigItem = {
  name?: string;
};

@Component({
  name: 'nsi-main-page',
})
export default class NsiMainPage extends Vue {
  get items() {
    const list = Object.entries(config) as Array<[string, TConfigItem]>;
    return list.reduce<TDictionaryItem[]>((result, [key, value]) => {
      if (value.name) {
        return [
          ...result,
          {
            id: key,
            label: value.name,
            to: { name: 'nsi.list', params: { mask: key } },
          },
        ];
      }

      return result;
    }, []);
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.title {
  font-style: normal;
  font-weight: 500;
  font-size: 24px;
  line-height: 24px;
  color: $black-color;
  margin-bottom: 19px;

  @include respond-to('medium') {
    font-size: 22px;
  }

  @include respond-to('small') {
    font-size: 18px;
  }
}

.nsi-list {
  &__item {
    color: $gold-dark-color;
    display: block;
    font-size: 14px;
    line-height: 16px;
    padding-left: 15px;
    position: relative;

    &::before {
      position: absolute;
      top: 3px;
      left: 0;
      height: 10px;
      width: 10px;
      background: url('/icons/arrow.svg') no-repeat;
      background-position: center;
      background-size: contain;
      content: '';
      display: block;
    }
  }

  img {
    margin-right: 5px;
  }
}
</style>
