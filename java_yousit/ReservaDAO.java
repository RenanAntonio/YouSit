package com.br;


import com.mysql.jdbc.log.Log;
import static java.lang.Math.log;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.List;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author 12131100068
 */
public class ReservaDAO {
    public ReservaDAO( ){
        
    }
    
       public List<Reserva> listaReservas() throws SQLException{
          List<Reserva> l = new ArrayList<Reserva>(   );
          Connection conn = new Conexao().getConexao();
          String sql = "select * from RESERVAS;";
          PreparedStatement pst = conn.prepareStatement(sql);
          
          ResultSet r = pst.executeQuery(sql);
          
         while (r.next()){
         
          Reserva reserva = new Reserva();
         
          reserva.setEspecial(r.getString("especial"));
          reserva.setId_reserva(r.getString("id_reserva"));
          reserva.setNome(r.getString("nome"));
          reserva.setQtdelugares(r.getInt("qtdelugares"));
          reserva.setTelefone(r.getString("telefone"));
          reserva.setToken(r.getString("token"));
          reserva.setNotificationValue(r.getInt("notificationValue"));
              
          l.add(reserva);
          }
        
        
        return l;
    }
    
   public boolean autentica(String id) throws SQLException
    {
        String sql = "SELECT * FROM RESERVAS where token=?";
        boolean retorno = false;
        
        PreparedStatement pst = new Conexao().getConexao().prepareStatement(sql);
        try {
           
            pst.setString(1, id);
            ResultSet res = pst.executeQuery();
            
            if(res.next())
            {
                retorno= true;
                                
            }
               
            
            
        } catch (SQLException ex) {
         
        }
        
        return retorno;
    
    
    }
}    
