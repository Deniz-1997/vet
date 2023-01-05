import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CountriesEditComponent } from './countries-edit.component';

describe('CountriesEditComponent', () => {
  let component: CountriesEditComponent;
  let fixture: ComponentFixture<CountriesEditComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ CountriesEditComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(CountriesEditComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
