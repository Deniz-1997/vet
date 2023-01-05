<script lang="ts">
import get from 'lodash/get';
import { Vue, Component, Prop } from 'vue-property-decorator';

@Component({
  name: 'FormMixin',
})
export default class extends Vue {
  @Prop({ type: Boolean, default: () => true }) readonly isShowcase!: boolean;
  @Prop({ type: Object, default: () => ({}) }) readonly changesData!: any;
  @Prop({ type: Object, default: () => ({}) }) readonly form!: any;

  get elevatorInfoChanges() {
    return this.changesData?.elevator_info_change ?? {};
  }

  getElevatorInfoEditCode(path) {
    return get(this.elevatorInfoChanges, path + '.editCode', '');
  }

  isEditCode(path, code) {
    return this.getElevatorInfoEditCode(path) === code;
  }

  getClass(path) {
    return this.getElevatorInfoEditCode(path).toLowerCase();
  }

  getProps(path) {
    const key = this.getClass(path);

    if (!key) return {};

    return { [key]: true };
  }
}
</script>
