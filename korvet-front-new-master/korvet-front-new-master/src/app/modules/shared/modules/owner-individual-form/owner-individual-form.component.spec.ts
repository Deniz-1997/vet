import {ComponentFixture, TestBed, waitForAsync} from '@angular/core/testing';

import {OwnerIndividualFormComponent} from './owner-individual-form.component';

describe('OwnerIndividualFormComponent', () => {
  let component: OwnerIndividualFormComponent;
  let fixture: ComponentFixture<OwnerIndividualFormComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [OwnerIndividualFormComponent]
    })
      .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(OwnerIndividualFormComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
