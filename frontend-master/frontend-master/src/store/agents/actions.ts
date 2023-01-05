import { AgentsService } from "@/api/agents/REST";

const Agents = new AgentsService();

export default {

  getList(_, form) {
    return Agents.getList(form);
  },

  getListAgents(_, id) {
    return Agents.getListAgents(id)
  },

  updateAgents(_, form) {
      return Agents.updateAgents(form);
  },

  createAgents(_, form) {
    return Agents.createAgents(form);
  },

  exportAgents(_, params) {
    return Agents.exportAgents();
  }
};