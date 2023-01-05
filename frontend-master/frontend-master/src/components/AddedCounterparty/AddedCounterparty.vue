<template>
  <div v-if="canAddNewManufacture">
    <div class="v-list-item v-list-item--link theme--light" @click="toggleModal">
      <div class="v-list-item__content">
        <div class="v-list-item__title">
          <span class="settingsSpan">
            <img src="/icons/add.svg" class="iconSettings" alt="" />
            Добавить организацию
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import { EAction, mapAccessFlags } from '@/utils';

@Component({
  name: 'added-counterparty',
  computed: {
    ...mapAccessFlags({
      canAddNewManufacture: EAction.CREATE_MANUFACTURER,
    }),
  },
})
export default class AddedCounterparty extends Vue {
  readonly canAddNewManufacture!: boolean;
  toggleModal() {
    this.$emit('showModal');
  }

  async saveOrganization() {
    this.$emit('close');
  }
}
</script>

<style lang="scss" scoped>
.settingsSpan {
  display: flex;
  align-items: center;

  .iconSettings {
    display: block;
    margin-right: 5px;
  }
}
</style>
