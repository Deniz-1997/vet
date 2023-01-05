import {Injectable} from '@angular/core';
import {AuthService} from './auth.service';

@Injectable({
  providedIn: 'root'
})
export class FilterUnitForByUserService {
  id: number;
  constructor(protected authService: AuthService) {
    this.authService.user$.subscribe((res) => {
      const unit_id = (this.authService.user$.value !== null) ? this.authService.user$.value.user['unit']['id'] : null;
      if (unit_id) {
        this.getUnit(unit_id);
      }
    });
  }

  getUnit(unit_id) {
    if (unit_id) {
      return this.id = unit_id;
    }
  }
}
