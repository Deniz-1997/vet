<template>
  <v-col class="mt-3" cols="12">
    <v-row v-show="edit || create" class="ma-0" justify="end">
      <v-col v-show="error !== null" class="right-align mr-3" cols="12">
        <text-component class="text-caption text-center orange--text" variant="span">
          {{ error }}
        </text-component>
      </v-col>
      <template v-if="create">
        <button-component
          title="Вернуться в реестр"
          class="mr-7"
          size="micro"
          @click="$router.push({ name: model.cancel_link })"
        />
        <button-component
          :disabled="error !== null"
          :loading="loading"
          title="Сформировать"
          size="micro"
          variant="primary"
          @click="$emit('create')"
        />
      </template>
    </v-row>
    <v-col cols="12">
      <v-row class="ma-0" justify="end">
        <v-col v-show="detail" class="mt-5" cols="12">
          <v-row class="ma-0" justify="end">
            <span class="mr-7">
              <button-component
                v-show="!edit"
                size="micro"
                title="Вернуться в реестр"
                @click="$router.push({ name: model.cancel_link })"
              />

              <slot name="extraReturnButton" />
            </span>

            <template v-if="isShow">
              <button-component v-show="isStatusCreatedAndEdit" title="Отмена" size="micro" @click="$emit('cancel')" />
              <modal-button
                v-if="isStatusCreated"
                button-text="Удалить"
                :modal-text="deleteTitle"
                btn-class="red lighten-2 white--text"
                primary
                width="540"
                variant-text="h5"
                @onResumeClick="$emit('delete')"
              />
              <button-component
                v-show="model.status_id === status.CREATE"
                :title="titleSave"
                size="micro"
                :disabled="edit && error !== null"
                variant="primary"
                @click="$emit('edit')"
              />
              <button-component
                v-show="isStatusCreated"
                size="micro"
                title="Подписать"
                variant="primary"
                @click="$emit('sign')"
              />
              <slot name="extraButton" />

              <slot name="revokeButton" :is-status-subscribed="isStatusSubscribed">
                <button-component
                  v-show="isStatusSubscribed"
                  size="micro"
                  title="Аннулировать"
                  @click="$emit('revoke')"
                />
              </slot>
            </template>
          </v-row>
        </v-col>
        <v-overlay :value="loading">
          <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
      </v-row>
    </v-col>
  </v-col>
</template>

<script lang="ts">
import { Component, Mixins } from 'vue-property-decorator';
import TextComponent from '@/components/common/Text/Text.vue';
import ButtonComponent from '@/components/common/buttons/DefaultButton.vue';
import ModalButton from '@/components/common/buttons/ModalButton.vue';
import { RshnButtonsMix } from '@/utils/mixins/rshn/rshnButtons';

@Component({
  components: {
    ModalButton,
    ButtonComponent,
    TextComponent,
  },
})
export default class RshnButtons extends Mixins(RshnButtonsMix) {}
</script>

<style scoped></style>
