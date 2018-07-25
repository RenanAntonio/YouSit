package com.br;


import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author 12131100068
 */
public class Conexao {
   public static final String DB = "jdbc:mysql://topsongs.com.br/topso213_YouSit";
   public static final String DRIVER = "com.mysql.jdbc.Driver";
   public static final String USER ="topso213";
   public static final String PASSWORD = "dirtydiana1";
   
   
           
   public Connection getConexao()  {
       Connection conn = null;
       
       try {
           System.out.println("Conectando...");
           
           Class.forName(DRIVER);
           conn = DriverManager.getConnection(DB, USER, PASSWORD);
           if (conn!=null){
               System.out.println("Conectado!");
           }
       } catch (SQLException e) {
               System.out.println("Erro "+e.getMessage());
           
       } catch (ClassNotFoundException e){
               System.out.println("Driver "+e.getMessage());
           
       }
        
   
       return conn;
    
}
}
