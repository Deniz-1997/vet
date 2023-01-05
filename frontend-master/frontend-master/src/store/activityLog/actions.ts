import { LoggingService } from "@/api/activityLog/REST";

const ActivityLog = new LoggingService();
type Params = {
  pageable: {
    pageNumber: number
    pageSize: number
  }
}
export default {
  getList(_, form: Params) {
    return ActivityLog.getList(form);
  },
};