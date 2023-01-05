import { AgentVueModel } from '@/models/Sdiz/Agent.vue';

export class AgentOgvVueModel extends AgentVueModel {
  available_filters: any[] = [...this.getAvailableFilters(), { name: 'owner_id', type: 'number' }];
  get_list_link = 'sdiz/getListAgent';
  link_registry = 'ogv_agent_list_sdiz';
  name_route_list = 'ogv_agent_list_sdiz';
  router_link = 'ogv_agent_view_sdiz';
}
