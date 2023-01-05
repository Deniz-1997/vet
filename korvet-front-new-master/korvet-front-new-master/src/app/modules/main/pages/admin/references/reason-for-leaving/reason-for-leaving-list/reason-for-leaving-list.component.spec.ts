import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ReasonForLeavingListComponent } from './reason-for-leaving-list.component';

describe('ReasonForLeavingListComponent', () => {
  let component: ReasonForLeavingListComponent;
  let fixture: ComponentFixture<ReasonForLeavingListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ReasonForLeavingListComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(ReasonForLeavingListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
