let student = {
    name: "Pooja",
    age: 20,
    grades: {
        math: 85,
        phy: 58
    },
    display: function() {
        console.log(`Name: ${this.name}`);
        console.log(`Age: ${this.age}`);
    },
    avg: function() {
        let total = this.grades.math + this.grades.phy;
        return total / 3;
    }
};

student.display();
console.log(`Avg grade: ${student.avg()}`);
student.age = 21;
student.display();