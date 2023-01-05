export interface EnvironmentInterface {
  production: boolean;
  credentials?: {client_id: string, client_secret: string};
  support?: {
    tell: string,
    tellText: string,
    email: string,
  };
}
