var _a;
var propName = "age";
var student = (_a = {},
    _a[propName] = 5,
    _a.name = "Ahsan",
    _a.printName = function () {
        console.log(this[propName]);
        console.log("".concat(this.name, "->").concat(this[propName]));
    },
    _a);
student.printName();
console.log("".concat(student.name));
