import {Pipe, PipeTransform} from '@angular/core';
import {OwnerModel} from '../../../models/owner/owner.models';

@Pipe({
  name: 'mainOwner'
})
export class MainOwnerPipe implements PipeTransform {

  transform(owners: OwnerModel[], path: string[], order: number) {
    if (!owners || !path || !order) {
      return owners;
    }
    return owners.sort((a: OwnerModel, b: OwnerModel) => {

      path.forEach(property => {
        a = a[property];
        b = b[property];
      });

      return a > b ? order : (order) * (-1);
    });
  }
}
