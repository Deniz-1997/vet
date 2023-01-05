export type TInteractionLogRequestItem = {
  start_date: string;
  end_date: string;
  initiator: string;
  type: { name: string };
  message_type: { name: string };
  request_name: string;
  status: { name: string };
  is_success?: boolean;
  error?: string;
  requestXml?: string;
  responseXml?: string;
};
