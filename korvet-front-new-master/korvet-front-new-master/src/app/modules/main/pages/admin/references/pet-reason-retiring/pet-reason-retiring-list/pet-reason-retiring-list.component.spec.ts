import { ComponentFixture, TestBed } from '@angular/core/testing';

import { PetReasonRetiringListComponent } from './pet-reason-retiring-list.component';

describe('PetReasonRetiringListComponent', () => {
  let component: PetReasonRetiringListComponent;
  let fixture: ComponentFixture<PetReasonRetiringListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ PetReasonRetiringListComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(PetReasonRetiringListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
