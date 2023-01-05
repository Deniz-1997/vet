<template>
  <div class="modalReasons">
    <div class="overlay" @click="clickClose" />
    <v-container class="storage-locations">
      <v-row>
        <v-col cols="12">
          <div class="title">
            <span class="spanTitle">{{ title }}</span>
            <img src="/icons/cross.svg" class="cross" @click="clickClose" />
          </div>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12">
          <div v-if="action === 'reject'" class="elementsInput">
            <span class="label">Причина отклонения</span>
            <SelectComponent v-model="selectValue" :items="listReject" item-value="id" item-text="name" />
          </div>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12">
          <div class="elementsInput">
            <span class="label">Комментарий</span>
            <InputComponent v-model="note" />
          </div>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12">
          <div class="buttons">
            <DefaultButton title="Отменить" @click="clickClose"> </DefaultButton>
            <DefaultButton title="Применить" variant="primary" @click="onConfirm"> </DefaultButton>
          </div>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script lang="ts">
import { Component, Vue, Prop } from 'vue-property-decorator';
import InputComponent from '@/components/common/inputs/InputComponent.vue';
import SelectComponent from '@/components/common/inputs/SelectComponent.vue';
import DefaultButton from '@/components/common/buttons/DefaultButton.vue';

@Component({
  name: 'modal-reasons',
  components: { InputComponent, SelectComponent, DefaultButton },
})
export default class ModalReasons extends Vue {
  @Prop({ type: String, default: '' }) public title!: string;
  @Prop({ type: String, default: '' }) public action!: string;
  @Prop({ type: Object, default: {} }) public item!: any;

  selectValue: any = null;
  note = '';
  listReject = [];

  created() {
    this.fetchRejectList();
  }

  clickClose() {
    this.$emit('click-close');
  }

  async fetchRejectList() {
    const { content } = await this.$store.dispatch('approvalTask/fetchRejectList', this.item.approval_request_type_id);
    return (this.listReject = content);
  }

  async onConfirm() {
    if (!this.selectValue) {
      this.$store.commit('errors/clearErrorList');
      this.$store.commit('errors/setErrorsList', "Поле 'Причина отклонения' не заполнено");
    } else {
      await this.$store.dispatch('approvalTask/rejectTask', {
        ...this.item,
        reject_reason_id: this.selectValue ? this.selectValue : null,
        note: this.note,
      });
      this.$emit('click-close');
      this.$emit('update-information');
    }
  }
}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';

.storage-locations {
  position: fixed;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
  z-index: 100;
  background: $white-color;
  border: 1px solid $input-border-color;
  box-sizing: border-box;
  box-shadow: 0 16px 32px rgba($black-color, 0.1);
  border-radius: 4px;
  max-width: 500px;
  width: 100%;
  max-height: 800px;
  overflow-y: auto;
  padding: 20px;
}

.close {
  display: flex;
  justify-content: flex-end;
  cursor: pointer;
}

.overlay {
  background-color: rgba($black-color, 0.3);
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 9;
}

.container {
  overflow-y: auto;
}

.title {
  align-items: center;
  display: flex;
  justify-content: space-between;
  font-style: normal;
  font-weight: 500;
  font-size: 24px;
  line-height: 24px;
  color: $black-color;

  @include respond-to('medium') {
    font-size: 22px;
  }

  @include respond-to('small') {
    font-size: 18px;
  }
}

.modalReasons {
  .row {
    width: auto !important;
  }
}

.label {
  color: $input-border-color;
  font-size: 16px;
  line-height: 16px;
  margin-bottom: 5px;
  display: block;
}

.buttons {
  display: flex;
  margin-top: 20px;
  justify-content: flex-end;
}

.button {
  background-color: $white-color;
  color: $medium-grey-color;
  padding: 15px 35px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  border-radius: 4px;
  margin-left: 15px;
  border: 1px solid $input-border-color;

  cursor: pointer;
  outline: none;

  @include respond-to('medium') {
    padding: 9px 25px;
  }

  @include respond-to('small') {
    padding: 5px 20px;
  }

  &:hover {
    box-shadow: 0 0 5px rgba($black-color, 0.5);
  }

  &--primary {
    border-color: $button-primary-background;
    background-color: $button-primary-background;
    color: $white-color;
  }
}

.select {
  height: 40px;
  width: 320px;

  &--lg {
    width: 100%;
  }

  &--small {
    width: 100%;
  }
}
.inputContainer {
  position: relative;
}
.inputContainer:hover {
  .pop-up {
    display: block;
  }
}
</style>
