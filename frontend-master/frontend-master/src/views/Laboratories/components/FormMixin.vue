<script lang="ts">
import { Vue, Component, Prop } from 'vue-property-decorator';

@Component({
  name: 'FormMixin',
})
export default class extends Vue {
  @Prop({ type: Boolean, default: () => true }) readonly isShow!: boolean;
  @Prop({ type: Boolean, default: () => false }) readonly isEdit!: boolean;
  @Prop({ type: Object, default: () => {} }) readonly form!: any;
}
</script>
