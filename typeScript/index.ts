const propName = "age";

type Animal = {
  [propName]: number;
  printName(): void;
};

const student: Animal & { name: string } = {
  [propName]: 5,
  name: "Ahsan",
  printName() {
    console.log(this[propName]);
    console.log(`${this.name}->${this[propName]}`);
  },
};

student.printName();
console.log(`${student.name}`);
