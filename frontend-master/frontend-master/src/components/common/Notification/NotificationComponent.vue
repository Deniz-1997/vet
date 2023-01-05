<template>
  <transition
    @after-enter="handleAfterEnter"
    name="shift"
    mode="out-in"
    appear
    :duration="ms"
  >
    <div
      v-if="activeNotification"
      :class="[
        'notification',
        {
          error: activeNotification.type === 'error',
          success: activeNotification.type === 'success',
        }
      ]"
      :key="notificationKey"
    >
      <text-component
        class="title"
        variant="h3"
      >
        {{ activeNotification.title }}
      </text-component>
      <text-component
        class="text"
        variant="p"
      >
        {{ activeNotification.text }}
      </text-component>
      <text-component
        v-for="text in activeNotification.list"
        class="text list"
        variant="p"
        :key="text"
      >
        {{ text }}
      </text-component>
    </div>
  </transition>
</template>

<script lang="ts">
import { Component, Prop, Vue, Watch } from 'vue-property-decorator';
import { Notification } from './Notification.types';
import TextComponent from '@/components/common/Text/Text.vue';

@Component({
  name: 'notification-component',
  components: {
    TextComponent,
  },
})
export default class NotificationComponent extends Vue {
  @Prop({ type: Array, default: () => [] }) notifications!: Notification[];
  @Prop({ type: Number, default: 5000 }) ms!: number;

  activeNotification: Notification | null = null;
  notificationKey: number | null = null;

  handleAfterEnter(): void {
    const isLastNotification = this.notificationKey === this.notifications.length;

    if (isLastNotification) {
      this.activeNotification = null;
      this.notificationKey = null;
      this.$emit('update:notifications', []);
    }
  }

  @Watch('notifications', { deep: true })
  onNotificationsChange(notifications: Notification[]): void {
    if (notifications.length) {
      notifications.forEach((notification, index) => {
        setTimeout(() => {
          this.activeNotification = notification;
          this.notificationKey = index + 1;
        }, index * this.ms);
      });
    }
  }
}
</script>

<style scoped lang='scss'>
  @import "@/assets/styles/_variables.scss";

  // $bright: map-get($map: $theme-colors, $key: "bright");
  // $dark: map-get($map: $theme-colors, $key: "dark");
  // $error: map-get($map: $theme-colors, $key: "error");
  // $primary: map-get($map: $theme-colors, $key: "primary");
  // $success: map-get($map: $theme-colors, $key: "success");
  // $white: map-get($map: $theme-colors, $key: "white");

  .shift-enter-active {
    opacity: 1;
    transform: none;
  }

  .shift-leave-active {
    opacity: 0;
    transform: translateY(-100%);
  }

  .shift-enter {
    opacity: 0;
    transform: translateY(-100%);
  }

  .notification {
    align-items: center;
    background: $dark;
    display: flex;
    flex-direction: column;
    left: 0;
    padding: 10px 0 15px;
    position: fixed;
    top: 0;
    transition: transform 1s, opacity 1s;
    transition-delay: 1s;
    width: 100%;
    z-index: map-get($zIndexes, "notification") !important;

    &.error {
      background: $error;
    }

    &.success {
      background: $success;
    }

    &:after {
      background: $primary;
      bottom: 0;
      content: "";
      height: 5px;
      left: 0;
      position: absolute;
      transition: width .8s .7s;
      width: 100%;
    }
  }

  .title {
    color: $white !important;
    padding: 0 0 10px;
  }

  .text {
    color: $white;
    display: flex;
    justify-content: center;
    margin: 0;
    padding: 5px 0 10px;
    position: relative;

    &:before {
      background: $bright;
      content: "";
      display: block;
      height: 1px;
      left: 0;
      position: absolute;
      top: 0;
      transition: width 1s .5s;
      width: 0;
    }

    &.list {
      margin: 0;
      padding: 5px 0 0;
    }
  }
</style>
