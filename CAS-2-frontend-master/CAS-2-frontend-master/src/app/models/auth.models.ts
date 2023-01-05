// import { constructByInterface } from '../utils/construct-by-interface';
//
// export interface GetTokenParamsInterface {
//   grant_type?: string;
//   scope?: string;
//   client_id?: string;
//   client_secret?: string;
//   username?: string;
//   password?: string;
//   refresh_token?: string;
// }
//
// export interface TokenAuthInterface {
//   access_token: string; // "Mzk1MDVhMzRjODUwMDdkZTBhMDFmZjdlZmRkMmUwZjI2ODU2YjJhNmU0ZDU2ZjFlNTg3Y2I5NDg0OWRmMWVmZQ",
//   expires_in: number; // 3600,
//   token_type: string; // "bearer",
//   scope: string; // "default",
//   refresh_token: string; // "ZTdlOWE5OGMzOGUzZTJmZTE1NjMzNDRjN2JjODVkZTA1MTc0ODI0ZDk3MDUxMTc4MGJkY2M3NjBlNjk3ZWExNg"
//   timestamp?: number;
// }
//
// export class TokenAuthModel implements TokenAuthInterface {
//   access_token: string; // "Mzk1MDVhMzRjODUwMDdkZTBhMDFmZjdlZmRkMmUwZjI2ODU2YjJhNmU0ZDU2ZjFlNTg3Y2I5NDg0OWRmMWVmZQ",
//   expires_in: number; // 3600,
//   token_type: string; // "bearer",
//   scope: string; // "default",
//   refresh_token: string; // "ZTdlOWE5OGMzOGUzZTJmZTE1NjMzNDRjN2JjODVkZTA1MTc0ODI0ZDk3MDUxMTc4MGJkY2M3NjBlNjk3ZWExNg"
//
//   constructor(o: TokenAuthInterface) {
//     constructByInterface(o, this);
//   }
// }
//
// export interface CompanyInterface {
//   name: string;
//   address: string;
//   phone: string;
//   hours: Array<any>;
// }
//
// export class CompanyModel implements CompanyInterface {
//   name: string;
//   address: string;
//   phone: string;
//   hours: Array<any>;
//
//   constructor(o?: CompanyInterface) {
//     constructByInterface(o, this);
//   }
// }
//
// export interface UserAdditionalFieldsInterface {
//   phone: string;
//   hours: Array<any>;
//   company: CompanyInterface;
//   position: string;
//   theme: string;
// }
//
// export class UserAdditionalFieldsModel implements UserAdditionalFieldsInterface{
//   phone: string;
//   hours: Array<any>;
//   company: CompanyModel;
//   position: string;
//   theme: string;
//   photoSrc: string;
//
//   constructor(o?: UserAdditionalFieldsInterface) {
//     constructByInterface(o, this, {company: CompanyModel});
//   }
// }
//
// export interface UserInterface {
//   id: number;
//   username: string; // "root",
//   email: string; // "krasnovvy@gmail.com",
//   plainPassword: any;
//   name: string;
//   surname: string;
//   patronymic: string;
//   additionalFields: UserAdditionalFieldsInterface;
// }
//
// export class UserModel implements UserInterface {
//   id: number;
//   username: string; // "root",
//   email: string; // "krasnovvy@gmail.com",
//   plainPassword: any;
//   name: string;
//   surname: string;
//   patronymic: string;
//   additionalFields: UserAdditionalFieldsModel;
//
//   constructor(o?: UserInterface) {
//     constructByInterface(o, this, {additionalFields: UserAdditionalFieldsModel});
//   }
//
//   get FullName() {
//     return [this.surname, this.name, this.patronymic].join(' ');
//   }
//
// }
//
// export interface UserAuthInterface {
//   groups: [{
//     id: number,
//     name: string,
//     code: string,
//     externalId?: number;
//   }];
//   user: UserInterface;
//   clientId: string; // "1_3u3bpqxw736s4kgo0gsco4kw48gos800gscg4s4w8w80oogc8c";
// }
//
// export class UserAuthModel implements UserAuthInterface {
//   groups: [{
//     id: number,
//     name: string,
//     code: string,
//     externalId?: number;
//   }];
//   user: UserModel;
//   clientId: string; // "1_3u3bpqxw736s4kgo0gsco4kw48gos800gscg4s4w8w80oogc8c";
//
//   constructor(o?: UserAuthInterface) {
//     constructByInterface(o, this, {user: UserModel});
//   }
// }
//
