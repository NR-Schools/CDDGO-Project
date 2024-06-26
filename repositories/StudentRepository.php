<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/database/DatabaseConfig.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/StudentModel.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/MemberModel.php';


class StudentRepository
{

    private static function queryResultToStudent(array $queryResult, bool $includeMember): Student
    {
        $student = new Student();
        $student->StudID = $queryResult['StudID'];
        $student->UserID = $queryResult['StudID'];
        $student->StudNo = $queryResult['StudNo'];
        $student->FirstName = $queryResult['FirstName'];
        $student->LastName = $queryResult['LastName'];
        $student->Program = $queryResult['Program'];
        $student->Email = $queryResult['Email'];
        $student->Password = $queryResult['Password'];
        $student->isVerified = $queryResult['isVerified'];

        $member = null;
        if ($includeMember && isset($queryResult['MemberID'])) {
            $member = new Member();
            $member->Position = $queryResult['Position'];
            $member->YearJoined = $queryResult['YearJoined'];
        }

        $student->member = $member;

        return $student;
    }

    static function getStudentByEmail(string $email): Student|null
    {
        $queryResult = Database::SQLwithFetch(
            Database::getPDO(),
            "
            SELECT * FROM STUDENTS
                INNER JOIN USERS
                    ON STUDENTS.StudID = USERS.UserID
                LEFT JOIN MEMBERS
                    ON STUDENTS.StudID = MEMBERS.MemberID
            WHERE Email = :email
            ",
                [":email" => $email]
        );

        $resultStudent = null;
        foreach ($queryResult as $studentResult) {
            $resultStudent = self::queryResultToStudent($studentResult, true);
            break;
        }

        return $resultStudent;
    }

    static function getAllStudents(): array
    {
        $queryResult = Database::SQLwithFetch(
            Database::getPDO(),
            "
            SELECT * FROM STUDENTS
                INNER JOIN USERS
                    ON STUDENTS.StudID = USERS.UserID
                LEFT JOIN MEMBERS
                    ON STUDENTS.StudID = MEMBERS.MemberID;
            ",
            []
        );

        $studentList = [];
        foreach ($queryResult as $studentResult) {
            $studentList[] = self::queryResultToStudent($studentResult, true);
        }

        return $studentList;
    }

    static function getStudentById(int $studId): Student
    {
        $resultQuery = Database::SQLwithFetch(
            Database::getPDO(),
            "
            SELECT * FROM STUDENTS
                INNER JOIN USERS
                    ON STUDENTS.StudID = USERS.UserID
                LEFT JOIN MEMBERS
                    ON STUDENTS.StudID = MEMBERS.MemberID
            WHERE StudID = :studId
            ",
            [":studId" => $studId]
        );

        $resultStudent = null;
        foreach ($resultQuery as $studentResult) {
            $resultStudent = self::queryResultToStudent($studentResult, true);
            break;
        }

        return $resultStudent;
    }

    static function addNewStudent(Student $student): bool {
        return Database::SQLwithoutFetch(
            Database::getPDO(),
            "
            INSERT INTO STUDENTS
            VALUES (
                :studId,
                :studentNumber,
                :firstName,
                :lastName,
                :program,
                false
            );
            ",
            [
                ":studId" => $student->StudID,
                ":studentNumber" => $student->StudNo,
                ":firstName" => $student->FirstName,
                ":lastName" => $student->LastName,
                ":program" => $student->Program
            ]
        );
    }

    static function updateStudent(Student $student): bool
    {
        return Database::SQLwithoutFetch(
            Database::getPDO(),
            "
            UPDATE STUDENTS
            SET
                StudNo = :studNo,
                FirstName = :firstName,
                LastName = :lastName,
                Program = :program,
                isVerified = :isVerified
            WHERE
                StudID = :studId
            ",
            [
                ":studId" => $student->StudID,
                ":studNo" => $student->StudNo,
                ":firstName" => $student->FirstName,
                ":lastName" => $student->LastName,
                ":program" => $student->Program,
                ":isVerified" => $student->isVerified
            ]
        );
    }

    static function deleteStudent(int $studentId): bool
    {
        return Database::SQLwithoutFetch(
            Database::getPDO(),
            "
            DELETE FROM STUDENTS WHERE StudID = :studId
            ",
            [ ":studId" => $studentId ]
        );
    }
}

?>