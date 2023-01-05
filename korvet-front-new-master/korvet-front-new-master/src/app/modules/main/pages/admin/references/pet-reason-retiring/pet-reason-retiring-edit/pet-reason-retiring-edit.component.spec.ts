import { ComponentFixture, TestBed } from '@angular/core/testing';

import { PetReasonRetiringEditComponent } from './pet-reason-retiring-edit.component';

describe('PetReasonRetiringEditComponent', () => {
  let component: PetReasonRetiringEditComponent;
  let fixture: ComponentFixture<PetReasonRetiringEditComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ PetReasonRetiringEditComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(PetReasonRetiringEditComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
