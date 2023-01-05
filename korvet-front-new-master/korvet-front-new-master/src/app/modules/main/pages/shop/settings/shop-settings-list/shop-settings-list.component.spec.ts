import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ShopSettingsListComponent } from './shop-settings-list.component';

describe('ShopSettingsListComponent', () => {
  let component: ShopSettingsListComponent;
  let fixture: ComponentFixture<ShopSettingsListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ShopSettingsListComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(ShopSettingsListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
