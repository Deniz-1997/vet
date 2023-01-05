import {Pipe, PipeTransform} from '@angular/core';
import {PetToOwnerModel} from '../../../models/pet/pet-to-owner.models';

@Pipe({
  name: 'petMainOwner'
})
export class PetMainOwnerPipe implements PipeTransform {

  transform(value: PetToOwnerModel[], args?: any): any {
    return value.find(petToOwner => petToOwner.mainOwner) || {};
  }

}
