import { IUserItemResponse, IUserItemRequest, IRoleSubjectItemResponse } from '@/services/models/user';
import { SubjectItem } from '@/services/mappers/auth';
import { TRoleResponseItem } from '@/services/models/roles';
import { Mapper } from '@/utils';
import { TMapperPlain, ISubjectData } from '@/services/models/common';
import { RoleItem } from '@/services/mappers/roles';

export class UserItem extends Mapper<IUserItemResponse> {
  @Mapper.catch()
  get id() {
    return this.get(({ user_id }) => user_id).required.value;
  }

  @Mapper.catch()
  get firstName() {
    return this.get(({ first_name }) => first_name).required.value;
  }

  @Mapper.catch()
  get lastName() {
    return this.get(({ last_name }) => last_name).required.value;
  }

  @Mapper.catch()
  get fatherName() {
    return this.get(({ second_name }) => second_name).optional.value;
  }

  @Mapper.catch()
  get email() {
    return this.get(({ email }) => email).optional.value;
  }

  @Mapper.catch()
  get login() {
    return this.get(({ login }) => login).required.value;
  }

  @Mapper.catch()
  get subjects() {
    return this.get(({ subject }) => this.mapSubjectList(subject)).required.value;
  }

  private mapRoleList(list: TRoleResponseItem[]) {
    return list.map((role) => new RoleItem(role).toJSON());
  }

  private mapSubject(subject: ISubjectData) {
    return new SubjectItem(subject).toJSON();
  }

  private mapSubjectList(list: IRoleSubjectItemResponse[]) {
    return list.map(({ subject, role }) => ({
      ...this.mapSubject(subject?.subject_data as ISubjectData),
      roles: this.mapRoleList(role),
    }));
  }

  toJSON() {
    return {
      id: this.id,
      firstName: this.firstName,
      lastName: this.lastName,
      fatherName: this.fatherName,
      email: this.email,
      login: this.login,
      subjects: this.subjects,
    };
  }
}

export class UserRequestOut extends Mapper<TMapperPlain<UserItem>> implements IUserItemRequest {
  @Mapper.catch()
  get user_id() {
    return this.get(({ id }) => id).required.value;
  }

  @Mapper.catch()
  get first_name() {
    return this.get(({ firstName }) => firstName).required.value;
  }

  @Mapper.catch()
  get last_name() {
    return this.get(({ lastName }) => lastName).required.value;
  }

  @Mapper.catch()
  get second_name() {
    return this.get(({ fatherName }) => fatherName).optional.value;
  }

  @Mapper.catch()
  get email() {
    return this.get(({ email }) => email).optional.value;
  }

  @Mapper.catch()
  get login() {
    return this.get(({ login }) => login).required.value;
  }

  @Mapper.catch()
  get subject() {
    const list = this.get(({ subjects }) => subjects).required.value;

    return list.map(({ roles, ...subject }) => ({
      subject_id: subject.id,
      subject: {
        subject_id: subject.id,
      },
      role: roles.map(({ id }) => ({ role_id: id })),
    }));
  }

  toJSON() {
    return {
      user_id: this.user_id,
      first_name: this.first_name,
      last_name: this.last_name,
      second_name: this.second_name,
      email: this.email,
      login: this.login,
      subject: this.subject,
    };
  }
}
