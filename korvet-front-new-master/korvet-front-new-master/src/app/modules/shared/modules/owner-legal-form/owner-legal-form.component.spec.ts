import {ComponentFixture, TestBed, waitForAsync} from '@angular/core/testing';

import {OwnerLegalFormComponent} from './owner-legal-form.component';

describe('OwnerLegalFormComponent', () => {
  let component: OwnerLegalFormComponent;
  let fixture: ComponentFixture<OwnerLegalFormComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [OwnerLegalFormComponent]
    })
      .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(OwnerLegalFormComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
